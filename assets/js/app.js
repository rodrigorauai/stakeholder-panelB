import {MDCTextField} from "@material/textfield/component";
import {MDCRipple} from "@material/ripple/component";

require('../css/app.scss');

document.querySelectorAll('.mdc-text-field').forEach(function (element) {
    new MDCTextField(element);
});

document.querySelectorAll('.mdc-button').forEach(function (element) {
    new MDCRipple(element);
});