document.addEventListener('alpine:init', () => {
    Alpine.data('recorder', ($el) => ({
        saved: false,
        data: {
            titulo_corto: {
                titulo: '',
                error: null,
            },
            titulo_largo: {
                titulo: '',
                error: null,
            },
            descripcion: {
                titulo: '',
                error: null,
            },
            status: '',
            elementos: null
        },
        submit: function (event, url) {
            let laravelToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            window.axios
                .put(url, this.data, { headers: { 'X-CSRF-TOKEN': laravelToken } })
                .then(res => {
                    console.log(res)
                })
                .catch(error => {
                    console.log(error)
                })

            this.setSaved();
        },
        setSaved: function () {
            setTimeout(() => {
                this.saved = false;
            }, 500);
        }
    }));
});