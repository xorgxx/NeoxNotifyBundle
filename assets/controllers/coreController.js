import {Controller} from '@hotwired/stimulus';

export class coreController extends Controller {
    const
    static values = {
        // Set Default for SweetAlert
        showCancelButton: { type: Boolean, default: true },
        showConfirmButton: { type: Boolean, default: true },
        showDenyButton: { type: Boolean, default: false },
        denyButtonText: { type: String, default: 'Annuler' },
        cancelButtonText: { type: String, default: 'Fermer' },
        confirmButtonText: { type: String, default: 'Envoyer' },
        title: { type: String, default: 'Recherche ...' },
        text: { type: String, default: 'Recherche ...' },
        locale: { type: String, default: 'fr' },
        toast:  { type: Boolean, default: false },
        background: { type: String, default: '#abd3c0' },   //
        icon: { type: String, default: 'info' },                // success, error, warning, info, question
        position: { type: String, default: 'center' },        //'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.
        replie: { type: String, default: 'Recherche ...' },
        url: { type: String, default: 'Fetching ...' },
    };
}