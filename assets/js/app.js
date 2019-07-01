import {MDCTopAppBar} from "@material/top-app-bar/component";
import {MDCTextField} from "@material/textfield/component";
import {MDCRipple} from "@material/ripple/component";
import {MDCTabBar} from "@material/tab-bar/component";
import {MDCSelect} from "@material/select/component";

import InitDrawer from './InitDrawer';

require('../css/app.scss');

let element;

document.querySelectorAll('.mdc-text-field').forEach(function (element) {
    new MDCTextField(element);
});

document.querySelectorAll('.mdc-button').forEach(function (element) {
    new MDCRipple(element);
});

document.querySelectorAll('.mdc-fab').forEach(function (element) {
    new MDCRipple(element);
});

document.querySelectorAll('.mdc-tab-bar').forEach(function (element) {
    new MDCTabBar(element);
});

document.querySelectorAll('.mdc-select').forEach(function (element) {
    new MDCSelect(element);
});

let drawer;
let drawerElement = document.querySelector('.mdc-drawer');

if (drawerElement) {
    drawer = InitDrawer(drawerElement);
}

element = document.querySelector('.mdc-top-app-bar');
if (element) {
    const topAppBar = new MDCTopAppBar(element);
    topAppBar.setScrollTarget(document.getElementById('main-content'));
    topAppBar.listen('MDCTopAppBar:nav', () => {
        if (drawer) {

            drawerElement.querySelectorAll('.mdc-list-item').forEach(function (element) {
                element.setAttribute('tabindex', drawer.open ? '-1' : '1');
            });

            drawer.open = !drawer.open;
        }
    });
}