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
                            <div class="card-header">Register</div>

                            <div class="card-body">
                                <form method="POST">
                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control" name="name" v-model="name" required autocomplete="name" autofocus>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" v-model="email" required autocomplete="email">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" v-model="password" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                                Register
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
            name : "",
            email : "",
            password : "",
            password_confirmation : ""
        }
    },
    methods : {
        handleSubmit(e) {
            e.preventDefault()

            const api_url = 'api/register';

            if (this.password === this.password_confirmation && this.password.length > 0)
            {
                axios.post(api_url, {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation : this.password_confirmation
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
                this.password = ""
                this.passwordConfirm = ""

                return alert('Passwords do not match')
            }
        }
    },
    beforeRouteEnter (to, from, next) {
        if (localStorage.getItem('jwt')) {
            return next('board');
        }

        next();
    }
}
</script>
