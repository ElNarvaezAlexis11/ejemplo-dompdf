document.addEventListener('alpine:init', () => {

    Alpine.data('recorder', ($el) => ({
        saved: false,
        data: {
            errors: null,
            titulo_corto: '',
            titulo_largo: '',
            descripcion: '',
            status: '',
            elementos: null,
        },
        submit: function (event, url) {
            let laravelToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            window.axios
                .put(url, this.data, { headers: { 'X-CSRF-TOKEN': laravelToken } })
                .then(res => {
                    if (res.data?.errors) {
                        this.data.errors = res.data.errors;
                        console.log(JSON.parse(res.data.errors));
                    }
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