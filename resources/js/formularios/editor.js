import { TYPE_INPUTS } from './types';
import { sorting } from './sortable';

// Espera a que se cargue AlpineJS y luego inicializa el componente.
document.addEventListener('alpine:init', () => {
    Alpine.data('editor', ($el) => ({
        // Array de elementos que se mostrarán en el formulario.
        elements: [
            {
                "title": "primera",
                "type": null,
                "values": [],
                "required": false,
                "validation": null,
                "errors": null,
                "position": 0
            },
            {
                "title": "segunda",
                "type": null,
                "values": [],
                "required": false,
                "validation": null,
                "errors": null,
                "position": 1
            },
            {
                "title": "tercera",
                "type": null,
                "values": [],
                "required": false,
                "validation": null,
                "errors": null,
                "position": 2
            },

        ],
        // Constante que contiene los tipos de elementos de entrada disponibles.
        TYPE_INPUTS: TYPE_INPUTS,
        init() {
            sorting($el, this.elements);
        },
        addElement: function () {
            // Añade un nuevo elemento al array de elementos.
            this.sort();

            this.elements.push(
                {
                    title: '',
                    type: null,
                    values: [],
                    required: false,
                    validation: null,
                    errors: null,
                    position: this.elements.length
                }
            );
        },
        remove: function (index) {
            // Elimina el elemento del array de elementos en la posición especificada.
            this.elements.splice(index, 1);
            this.sort();
            this.elements.forEach((element, position) =>{
                element.position = position;
            });
        },
        setType: function (event, element, type) {
            // Establece el tipo del elemento especificado e inicia los valores si es necesario.
            event.preventDefault();
            element.type = type
            if (type !== TYPE_INPUTS.CHECK.title && type !== TYPE_INPUTS.RADIO.title) {
                element.values = [];
            }
            if (!this.requireValidation(element)) {
                element.validation = null;
            }
        },
        addValues: function (element) {
            // Añade una nueva opción al elemento especificado.
            element.values.push('option X');
        },
        removeValue: function (element, position) {
            // Elimina la opción del elemento especificado en la posición dada.
            element.values.splice(position, 1);
        },
        addRowToGrid: function (element) {
            // Añade una nueva fila a la cuadrícula de opciones en el elemento especificado.
            if (!Array.isArray(element.values[0])) {
                element.values = [
                    [], //rows
                    [] //cols
                ];
            }
            element.values[0].push('row name');
        },
        removeRowToGrid: function (element, index) {
            // Elimina la fila de la cuadrícula de opciones del elemento especificado en la posición dada.
            element.values[0].splice(index, 1);
        },
        addColToGrid: function (element) {
            // Añade una nueva columna a la cuadrícula de opciones en el elemento especificado.
            if (!Array.isArray(element.values[1])) {
                element.values = [
                    [], //rows
                    [] //cols
                ];
            }
            element.values[1].push('col name');
        },
        removeColToGrid: function (element, index) {
            // Elimina la columna de la cuadrícula de opciones del elemento especificado en la posición dada.
            element.values[1].splice(index, 1);
        },
        setValidations: function (element) {
            // Establece las validaciones para el elemento especificado en función de su tipo.
            if (element.validation) {
                element.validation = null;
                return
            }
            switch (element.type) {
                case TYPE_INPUTS.PARAGRAPHS.title:
                    element.validation = {
                        value: null,
                        type: 'minum',
                    };
                    break;
                case TYPE_INPUTS.DATE.title:
                    element.validation = {
                        value: null,
                        type: 'between',
                    };
                    break;
                default:

                    break;
            }

        },
        requireValidation: function (element) {
            //Establece la propiedad de requerido para el campo dado.
            let requieredValidations = [
                TYPE_INPUTS.PARAGRAPHS.title,
                TYPE_INPUTS.DATE.title,
            ];
            if (requieredValidations.find((type) => type === element.type)) {
                return true;
            }
            return false;
        },
        sort:function(){
            this.elements.sort((elA,elB)=>{
                return elA.position - elB.position;
            });
        }
    }));
})