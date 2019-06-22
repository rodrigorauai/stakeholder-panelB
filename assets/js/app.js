import {MDCDrawer} from "@material/drawer/component";
import {MDCTopAppBar} from "@material/top-app-bar/component";
import {MDCTextField} from "@material/textfield/component";
import {MDCRipple} from "@material/ripple/component";

require('../css/app.scss');

let drawer;
let element;

document.querySelectorAll('.mdc-text-field').forEach(function (element) {
    new MDCTextField(element);
});

document.querySelectorAll('.mdc-button').forEach(function (element) {
    new MDCRipple(element);
});

element = document.querySelector('.mdc-drawer');

if (element) {
    drawer = new MDCDrawer(element);
}

element = document.querySelector('.mdc-top-app-bar');
if (element) {
    const topAppBar = new MDCTopAppBar(element);
    topAppBar.setScrollTarget(document.getElementById('main-content'));
    topAppBar.listen('MDCTopAppBar:nav', () => {
        if (drawer) {
            drawer.open = !drawer.open;
        }
    });
}