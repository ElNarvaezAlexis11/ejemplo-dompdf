import Sortable from "sortablejs"
// Espera a que se cargue AlpineJS y luego inicializa el componente.
document.addEventListener('alpine:init', () => {
    Alpine.data('editor', ($el, $wire) => ({
        init(){
            Sortable.create($el, {
                animation: 300,
                draggable: ".container-info",
                handle: ".drag",
                swap: true,
                chosenClass: '.scale-x-110',
                store: {
                    set: function(sortable){
                        let newOrder = sortable.toArray();
                        $wire.setNewOrder(newOrder);
                    }
                }
            });
        }
    }));
})