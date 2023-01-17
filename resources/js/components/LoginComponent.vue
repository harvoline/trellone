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
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <form method="POST">

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" v-model="email" required autocomplete="email" autofocus>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" v-model="password" required autocomplete="current-password">
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary" @click="handleLogin">
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                email : "",
                password : "",
                isLoggedIn : null,
                name : null
            }
        },
        created(){
            if (localStorage.getItem('jwt') != null) {
                window.location.href = '/home';
            }

            this.isLoggedIn = localStorage.getItem('jwt')
            this.name = localStorage.getItem('user')
            this.user_id = localStorage.getItem('user_id')

        },
        methods : {
            handleLogin(e) {
                e.preventDefault()

                const api_url = 'api/login';

                console.log(this.password);
                console.log(this.email);

                if (this.password.length > 0 && this.email.length > 0)
                {
                    axios.post(api_url, {
                            email: this.email,
                            password: this.password
                        })
                        .then(response => {
                            localStorage.setItem('user_id',response.data.user_id)
                            localStorage.setItem('user',response.data.name)
                            localStorage.setItem('jwt',response.data.token)
                            console.log(localStorage.getItem('jwt'))

                            if (localStorage.getItem('jwt') != null){
                                window.location.href = '/home';
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    this.email = ""
                    this.password = ""

                    return alert('Authentication failed!')
                }
            }
        },
    }
</script>

