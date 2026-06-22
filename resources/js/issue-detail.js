const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function request(url, options = {}) {
    const headers = {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(options.headers ?? {}),
    };

    if (options.body) {
        headers['Content-Type'] = 'application/json';
    }

    return fetch(url, { ...options, headers });
}

function formatDate(value) {
    if (!value) {
        return '';
    }

    return new Date(value).toLocaleString();
}

function initTags() {
    const manager = document.getElementById('tag-manager');

    if (!manager) {
        return;
    }

    const issueId = manager.dataset.issueId;
    const currentContainer = document.getElementById('current-tags');
    const select = document.getElementById('tag-select');
    const attachButton = document.getElementById('attach-tag');

    let allTags = [];
    let currentTags = JSON.parse(document.getElementById('current-tags-data')?.textContent || '[]');

    function renderOptions() {
        const attached = new Set(currentTags.map((tag) => tag.id));
        select.innerHTML = '<option value="">Select a tag</option>';

        allTags
            .filter((tag) => !attached.has(tag.id))
            .forEach((tag) => {
                const option = document.createElement('option');
                option.value = tag.id;
                option.textContent = tag.name;
                select.appendChild(option);
            });
    }

    function renderCurrent() {
        currentContainer.innerHTML = '';

        if (currentTags.length === 0) {
            const empty = document.createElement('span');
            empty.className = 'text-sm text-gray-500';
            empty.textContent = 'No tags.';
            currentContainer.appendChild(empty);
        } else {
            currentTags.forEach((tag) => {
                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium text-white';
                chip.style.backgroundColor = tag.color || '#6b7280';
                chip.textContent = tag.name;

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'ml-1 leading-none text-white/80 hover:text-white';
                remove.textContent = '×';
                remove.setAttribute('aria-label', `Remove ${tag.name}`);
                remove.addEventListener('click', () => detachTag(tag.id));

                chip.appendChild(remove);
                currentContainer.appendChild(chip);
            });
        }

        renderOptions();
    }

    function attachTag(tagId) {
        request(`/issues/${issueId}/tags/${tagId}`, { method: 'POST' })
            .then((response) => response.json())
            .then((tags) => {
                currentTags = tags;
                renderCurrent();
            });
    }

    function detachTag(tagId) {
        request(`/issues/${issueId}/tags/${tagId}`, { method: 'DELETE' })
            .then((response) => response.json())
            .then((tags) => {
                currentTags = tags;
                renderCurrent();
            });
    }

    attachButton.addEventListener('click', () => {
        const tagId = select.value;

        if (tagId) {
            attachTag(tagId);
        }
    });

    request('/tags')
        .then((response) => response.json())
        .then((tags) => {
            allTags = tags;
            renderCurrent();
        });
}

function initComments() {
    const container = document.getElementById('comments');

    if (!container) {
        return;
    }

    const issueId = container.dataset.issueId;
    const list = document.getElementById('comment-list');
    const form = document.getElementById('comment-form');
    const loadMore = document.getElementById('load-more');

    let nextPageUrl = `/issues/${issueId}/comments`;

    function buildComment(comment) {
        const wrapper = document.createElement('div');
        wrapper.dataset.comment = '';
        wrapper.className = 'border-b border-gray-100 py-3 last:border-0';

        const header = document.createElement('div');
        header.className = 'flex items-center justify-between';

        const author = document.createElement('span');
        author.className = 'text-sm font-medium text-gray-900';
        author.textContent = comment.author_name;

        const date = document.createElement('span');
        date.className = 'text-xs text-gray-500';
        date.textContent = formatDate(comment.created_at);

        header.append(author, date);

        const body = document.createElement('p');
        body.className = 'mt-1 whitespace-pre-line text-sm text-gray-700';
        body.textContent = comment.body;

        wrapper.append(header, body);

        return wrapper;
    }

    function refreshEmptyState() {
        const hasComments = list.querySelector('[data-comment]');
        let note = list.querySelector('#no-comments');

        if (hasComments) {
            note?.remove();
        } else if (!note) {
            note = document.createElement('p');
            note.id = 'no-comments';
            note.className = 'py-4 text-sm text-gray-500';
            note.textContent = 'No comments yet.';
            list.appendChild(note);
        }
    }

    function clearErrors() {
        form.querySelectorAll('[data-error]').forEach((element) => {
            element.textContent = '';
        });
    }

    function showErrors(errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const target = form.querySelector(`[data-error="${field}"]`);

            if (target) {
                target.textContent = messages[0];
            }
        });
    }

    function loadPage(url) {
        return request(url)
            .then((response) => response.json())
            .then((page) => {
                page.data.forEach((comment) => list.appendChild(buildComment(comment)));
                nextPageUrl = page.next_page_url;
                loadMore.hidden = !nextPageUrl;
                refreshEmptyState();
            });
    }

    loadMore.addEventListener('click', () => {
        if (nextPageUrl) {
            loadPage(nextPageUrl);
        }
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearErrors();

        const payload = {
            author_name: form.author_name.value,
            body: form.body.value,
        };

        request(`/issues/${issueId}/comments`, {
            method: 'POST',
            body: JSON.stringify(payload),
        }).then((response) => {
            if (response.status === 422) {
                return response.json().then((data) => showErrors(data.errors));
            }

            return response.json().then((comment) => {
                const note = list.querySelector('#no-comments');
                note?.remove();
                list.prepend(buildComment(comment));
                form.reset();
            });
        });
    });

    loadPage(nextPageUrl);
}

document.addEventListener('DOMContentLoaded', () => {
    initTags();
    initComments();
});
