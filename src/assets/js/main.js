import Vue from 'vue';
import RefTagsModal from "./RefTagsModal";

new Vue({
    components: {RefTagsModal},
    el:'#ref-tags-app',
    data() {
        return {
            isOn: false
        }
    },
    mounted() {
        let trigger = window.refTagsUiTrigger.split('+').map(p=>p.toLowerCase().trim())

        let requireCtrl = trigger.includes('ctrl');
        let requireAlt = trigger.includes('alt');
        let requireShift = trigger.includes('shift');
        let letter = trigger.pop()

        document.onkeydown = (e) => {
            if (e.ctrlKey===requireCtrl &&
                e.altKey===requireAlt &&
                e.shiftKey===requireShift &&
                e.key.toLowerCase()===letter) {
                this.isOn = true
            }
        }
    }
})

