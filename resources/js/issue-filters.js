function initIssueFilters() {
    const form = document.getElementById('issue-filter-form');
    const results = document.getElementById('issue-results');

    if (!form || !results) {
        return;
    }

    let timer;

    function buildUrl() {
        const params = new URLSearchParams();

        for (const [key, value] of new FormData(form).entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }

        const query = params.toString();

        return query ? `${form.action}?${query}` : form.action;
    }

    function refresh() {
        const url = buildUrl();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => response.text())
            .then((html) => {
                results.innerHTML = html;
                window.history.replaceState({}, '', url);
            });
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        refresh();
    });

    form.querySelector('[name="search"]').addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(refresh, 300);
    });

    form.querySelectorAll('select').forEach((select) => {
        select.addEventListener('change', refresh);
    });
}

document.addEventListener('DOMContentLoaded', initIssueFilters);
