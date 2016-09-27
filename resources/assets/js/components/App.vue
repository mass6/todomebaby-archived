<script>
    import { store } from '../store'
    import { service } from '../service'
    import Sidebar from './Sidebar.vue'
    export default {
        data(){
            return{
                sharedState: store.state
            }
        },
        components:{
            'sidebar':Sidebar
        },
        ready: function(){
            service.initialize();
            this.health();
        },
        methods: {
            health: function () {
                window.setInterval(function () {
                    Vue.http.get('/health');
                }, 15000);
            }
        },
        events: {
            listChanged: function() {
                this.$broadcast('listWasChanged');
            }
        }
    }
</script>