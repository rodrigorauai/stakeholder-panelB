import {MDCTextField} from "@material/textfield/component";

require('../css/app.scss');

document.querySelectorAll('.mdc-text-field').forEach(function (element) {
    new MDCTextField(element);
});
