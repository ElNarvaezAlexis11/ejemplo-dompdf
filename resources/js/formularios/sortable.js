import Sortable from "sortablejs"

export const sorting = ($editor, elements) => {
    return Sortable.create($editor, {
        animation: 400,
        draggable: ".container-info",
        handle: ".drag",
        chosenClass: 'scale-x-105',
        // store: {
        //     set: (sortable) => {
        //         let newOrder = sortable.toArray();
        //         //Buscamos al elemento que posea la posicion dada del arreglo
        //         for (let index = 0; index < newOrder.length; index++) {
        //             elements[parseInt(newOrder[index])].position = index;
        //         }
        //     }
        // }
        // onEnd: function (evt) {
        //     let oldPosition = evt.oldIndex;
        //     let newPosition = evt.newIndex;

        //     let elementMoved = elements.find((el) => el.position == oldPosition);
        //     let elementToMove = elements.find((el) => el.position == newPosition);

        //     elementMoved.position = newPosition; 

        // }
    });
};