import { TYPE_INPUTS } from './types';

document.addEventListener('alpine:init', () => {
    Alpine.data('form', () => ({
        TYPE_INPUTS: TYPE_INPUTS,
        elements: [
            {
                "title": "¿Pregunta 1?",
                "type": "text",
                "values": [],
                "required": true,
                "validation": null,
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 2?",
                "type": "paragraphs",
                "values": [],
                "required": false,
                "validation": {
                    "value": "10",
                    "type": "minum"
                },
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 3?",
                "type": "radio",
                "values": [
                    "option 1",
                    "option 2",
                    "option 3"
                ],
                "required": true,
                "validation": null,
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 4?",
                "type": "check",
                "values": [
                    "option 1",
                    "option 2",
                    "option 3"
                ],
                "required": false,
                "validation": null,
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 5?",
                "type": "grid-verify",
                "values": [
                    [
                        "row name 1",
                        "row name 2 ",
                        "row name 3"
                    ],
                    [
                        "col name 1",
                        "col name 2",
                        "col name 3"
                    ]
                ],
                "required": true,
                "validation": null,
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 6?",
                "type": "grid-multiply",
                "values": [
                    [
                        "row name 1",
                        "row name 2"
                    ],
                    [
                        "col name 1",
                        "col name 2"
                    ]
                ],
                "required": true,
                "validation": null,
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 7?",
                "type": "date",
                "values": [],
                "required": false,
                "validation": {
                    "value": "2023-02-20",
                    "type": "after"
                },
                errors:'El campo debe es requerido'
            },
            {
                "title": "¿Pregunta 8?",
                "type": "hour",
                "values": [],
                "required": true,
                "validation": null,
                errors:'El campo debe es requerido'
            }
        ],
        setProperties: function (element, $el) {
            if (element.required) {
                $el.setAttribute('required', true)
            }
            if (element.type === TYPE_INPUTS.DATE.title && element.validation) {

                switch (element.validation.type) {
                    case 'after':
                        $el.setAttribute('min',element.validation.value);
                        break;

                    case 'before':
                        $el.setAttribute('max',element.validation.value);
                        break;

                    default:
                        break;
                }
            }
        }
    }));
});