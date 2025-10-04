export function debounce<F extends (...args: any[]) => void>(func: F, waitFor: number) {
    let timeout: ReturnType<typeof setTimeout>;
    return function (...args: Parameters<F>) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func(...args);
        }, waitFor);
    };
}