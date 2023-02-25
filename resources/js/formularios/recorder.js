document.addEventListener('alpine:init', () => {

    Alpine.data('recorder', ($el) => ({
        saved: false,
        data: {
            errors: null,
            titulo_corto: '',
            titulo_largo: '',
            descripcion: '',
        }
    }));
});