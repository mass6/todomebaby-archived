<script>
    import { store } from '../store'
    import Sidebar from './Sidebar.vue'
    export default {
        data(){
            return{
                sharedState: store.state,
                store: store
            }
        },
        components:{
            'sidebar':Sidebar
        },
        ready: function(){
            this.store.initialize();
        },
        events: {
            listChanged: function() {
                this.$broadcast('listWasChanged');
            }
        }
    }
</script>