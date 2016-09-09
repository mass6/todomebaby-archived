<template>
    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">
            <p>Path: {{ $route.path }}</p>
            <p>Params: {{ $route.params | json }}</p>
            <p>Query: {{ $route.query | json }}</p>
            <p>Matched: {{ $route.query | json }}</p>
            <p>Name: {{ $route.name }}</p>
            <br/>
            <h2>{{ formName }}</h2>
            <!-- Grid -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input v-model="task.title" id="name" type="text" class="form-control pull-left" placeholder="Name" />
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>
<style>

</style>
<script>

    export default{
        props: {
            task: {
                type: Object,
                default: function () {
                    return {
                        title: 'Blank Title'
                    }
                }
            }
        },
        data(){
            return{
                formName:'New Form'
            }
        },
        route: {
            data: function(transition) {
                console.log('New Form');
            }
        }
    }
</script>