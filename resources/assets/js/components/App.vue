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
        },
        events: {
            listChanged: function() {
                this.$broadcast('listWasChanged');
            }
        }
    }
</script>