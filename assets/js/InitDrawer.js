import {MDCDrawer} from "@material/drawer/component";
import {MDCDialog} from "@material/dialog/component";

function InitDrawer(element)
{
    let drawer;

    let accountSwitcherButton = element.querySelector('.account-switcher');
    let accountSwitcherDialog = new MDCDialog(document.querySelector('#account-switcher--dialog'));

    if (accountSwitcherButton) {
        accountSwitcherButton.addEventListener('click', function () {
            accountSwitcherDialog.open();
        });
    }

    const mobileClass = "mdc-drawer--modal";
    const desktopClass = "mdc-drawer--dismissible";

    const isMobile = window.matchMedia(`(max-width: 1024px)`);

    const mobileOverlay = document.createElement("div");
    mobileOverlay.classList.add("mdc-drawer-scrim");

    function toggleMobile(event) {
        const isMobile = event.matches;

        if (isMobile) {
            element.classList.add(mobileClass);
            element.classList.remove('mdc-drawer--open');
            element.classList.remove(desktopClass);

            // Insert scrim/overlay element right after drawer
            element.insertAdjacentElement("afterend", mobileOverlay);
        } else {
            element.classList.add(desktopClass);
            element.classList.add('mdc-drawer--open');
            element.classList.remove(mobileClass);

            // Remove scrim/overlay element
            mobileOverlay.remove();
        }

        drawer = MDCDrawer.attachTo(element);

        if (isMobile) {
            drawer.open = false;
        }
    }

    isMobile.addListener(toggleMobile);

    toggleMobile(isMobile);

    return drawer;
}

export default InitDrawer;