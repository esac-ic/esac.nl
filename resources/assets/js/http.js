const token = document.querySelector('meta[name="csrf-token"]').content;

/**
 * Calls one of the site's own endpoints with Laravel's CSRF token, sending a plain object as JSON.
 * Resolves to the parsed JSON, or the text for other responses.
 */
export async function request(method, url, body) {
    const json = body !== undefined && !(body instanceof FormData);
    const response = await fetch(url, {
        method,
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token, ...(json && { 'Content-Type': 'application/json' }) },
        body: json ? JSON.stringify(body) : body,
    });

    if (!response.ok) {
        throw new Error(`${method} ${url} answered ${response.status}`);
    }

    return response.headers.get('Content-Type')?.includes('json') ? response.json() : response.text();
}
