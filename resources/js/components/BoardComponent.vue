<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a href="/" class="navbar-brand">Trellone</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <!-- Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <a href="/" class="nav-link" v-if="!isLoggedIn">Login</a>
                        <a href="/register" class="nav-link" v-if="!isLoggedIn">Register</a>
                        <li class="nav-link" v-if="isLoggedIn"> Hi, {{name}}</li>
                        <a href="/home" class="nav-link" v-if="isLoggedIn">Board</a>
                        <button type="submit" class="btn btn-primary" @click="handleLogout" v-if="isLoggedIn">
                            Logout
                        </button>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="row justify-content-center container m-auto">
                <div class="col-md-4" v-for="element,index in categories" :key="element.id">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{element.name}}</h4>
                        </div>
                        <div class="card-body">
                            <draggable v-bind="dragOptions" element="div" @end="changeOrder" v-model="element.tasks">
                                <transition-group :id="element.id">
                                    <div v-for="task,index in element.tasks" :key="task.id+','+task.order" class="transit-1" :id="task.id">
                                        <div class="small-card" @dblclick="editTask(task)">
                                            <textarea v-if="task === editingTask" class="text-input" @keyup.enter="endEditing(task)" @blur="endEditing(task)" v-model="task.name"></textarea>
                                            <label for="checkbox" v-if="task !== editingTask">{{ task.name }}</label>
                                            <button class="btn-close btn-close-pos" @click="deleteTask(task)"></button>
                                        </div>
                                    </div>
                                </transition-group>
                            </draggable>
                            <div class="small-card button" @click="addNew(index)">
                                <h5 class="text-center">Add new card</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        components: {
            draggable
        },
        data(){
            return {
                isLoggedIn : null,
                name : null,
                categories : [],
                editingTask : null
            }
        },
        mounted(){
            this.isLoggedIn = localStorage.getItem('jwt')
            this.name = localStorage.getItem('user')
            this.user_id = localStorage.getItem('user_id')

            let token = localStorage.getItem('jwt')

            if (localStorage.getItem('jwt') == null) {
                window.location.href = '/';
            }

            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

            axios.get('api/category').then(response => {
                response.data.forEach((data) => {
                    this.categories.push({
                        id : data.id,
                        name : data.name,
                        tasks : []
                    })
                })
                this.loadTasks()
            })
        },
        methods : {
            handleLogout(e) {
                e.preventDefault();

                localStorage.removeItem('jwt');
                localStorage.removeItem('user');
                localStorage.removeItem('user_id');

                this.isLoggedIn = null;
                this.name = null;

                window.location.href = '/';
            },
            addNew(id) {
                let user_id = 1
                let name = "New task"
                let category_id = this.categories[id].id
                let order = this.categories[id].tasks.length

                axios
                    .post('api/task', {user_id, name, order, category_id})
                    .then(response => {
                        this.categories[id].tasks.push(response.data.data)
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            loadTasks() {
                this.categories.map(category => {
                    axios
                        .get(`api/category/${category.id}/tasks`)
                        .then(response => {
                            category.tasks = response.data;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                })
            },
            changeOrder(data){
                let toTask = data.to
                let fromTask = data.from
                let task_id = data.item.id
                let category_id = toTask.id
                let order = data.newIndex == data.oldIndex ? false : data.newIndex

                if (order !== false) {
                    axios
                        .patch(`api/task/${task_id}`, {
                            order, category_id
                        })
                        .then(response => {

                        });
                }
            },
            endEditing(task) {
                this.editingTask = null

                axios
                    .patch(`api/task/${task.id}`, {name: task.name})
                    .then(response => {

                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            editTask(task) {
                this.editingTask = task
            },
            deleteTask(task) {

                if (task) {
                    axios
                        .delete(`api/task/${task.id}`)
                        .then(response => {
                            let index = this.categories[task.category_id - 1].tasks.indexOf(task);

                            if (index > -1) {
                                this.categories[task.category_id - 1].tasks.splice(index, 1);
                            }

                        })
                        .catch(error => {
                            console.error(error);
                        });
                }

            }
        },
        computed: {
            dragOptions () {
                return  {
                    animation: 1,
                    group: 'description',
                    ghostClass: 'ghost'
                };
            },
        }
    }
</script>
