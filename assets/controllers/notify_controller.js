
import Swal from "sweetalert2";
import {coreController} from "./coreController";

export default class extends coreController {
    initialize() {
        // guarantees "this" refers to this object in _onConnect
        this._onConnect = this._onConnect.bind(this);
        this._eventSource = null; // Stockez une référence à l'EventSource
    }

    connect() {
        // Créez un EventSource pour "/my/topic/1"https://neonatis.com:9021/.well-known/mercure?topic=/my/topic/1
        this._eventSource = new EventSource(this.urlValue);
        this._eventSource.addEventListener('message', this._onConnect);
    }

    disconnect() {
        // Fermez l'EventSource lorsque le contrôleur est déconnecté
        if (this._eventSource) {
            this._eventSource.close();
            this._eventSource = null;
        }
    }

    _onConnect(event) {
        const evenData = JSON.parse(event.data);

        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "info",
            title: evenData.data,
            showConfirmButton: false,
            timerProgressBar: true,
            background: "#4d2222",
            timer: 4500
        });
    }
}
