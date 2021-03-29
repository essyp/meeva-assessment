<template>
    <div class="container">
        <div class="row justify-content-center">
            
                    <div class="wrap-login100">
                        <form v-if="subscription_form" class="login100-form validate-form" id="subscribe-form">
                            <span class="login100-form-logo">
                                <i class="zmdi zmdi-landscape"></i>
                            </span>

                            <span class="login100-form-title p-b-34 p-t-27">
                                Newsletter Subscription
                            </span>

                            <div class="wrap-input100 validate-input" data-validate = "Enter Firstname">
                                <input class="input100" type="text" v-model="send_data.name" name="name" placeholder="First Name">
                                <span class="focus-input100"><i class="fa fa-user"></i></span>
                            </div>

                            <div class="wrap-input100 validate-input">
                                <input class="input100" type="email" v-model="send_data.email" name="email" placeholder="Email">
                                <span class="focus-input100"><i class="fa fa-envelope"></i></span>
                            </div>

                            <div class="container-login100-form-btn">
                                <button class="login100-form-btn" type="button" @click="subscribe()">
                                    Subscribe
                                </button>
                            </div>

                            <div class="text-center p-t-90">
                                <a class="txt1" href="javascript:void(0);" @click="showUnsubscribe()">
                                    Unsubscribe?
                                </a>
                            </div>
                        </form>

                        <form v-if="unsubscribe_form" class="login100-form validate-form" id="unsubscribe-form">
                            <span class="login100-form-logo">
                                <i class="zmdi zmdi-landscape"></i>
                            </span>

                            <span class="login100-form-title p-b-34 p-t-27">
                                Unsubscribe
                            </span>

                            <div class="wrap-input100 validate-input">
                                <input class="input100" type="email" v-model="send_data.email" name="email" placeholder="Your Email">
                                <span class="focus-input100"><i class="fa fa-envelope"></i></span>
                            </div>

                            <div class="container-login100-form-btn">
                                <button class="login100-form-btn" type="button" @click="unSubscribe()">
                                    Unsubscribe
                                </button>
                            </div>

                            <div class="text-center p-t-90">
                                <a class="txt1" href="javascript:void(0);" @click="showSubscribe()">
                                    Subscribe?
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        data() {
            return {
                send_data:{
                    name:'',
                    email:'',
                },
                subscription_form:true,
                unsubscribe_form:false,
            }
        },

        methods: {
            subscribe () {
                open_loader('#page');
                    var form = $("#subscribe-form")[0];
		            var _data = new FormData(form);
                    axios.post(this.baseURl + 'subscribe', _data)
                .then((response) => {
                if (response.data.status == 200) {
                    this.send_data.name = '';
                    this.send_data.email = '';
                    toastr.success(response.data.message);
                    close_loader('#page');
                }
                }).catch((error) =>{
                    toastr.error(error.response.data.message);
                    close_loader('#page');
                })
            },

            unSubscribe () {
                open_loader('#page');
                    var form = $("#unsubscribe-form")[0];
		            var _data = new FormData(form);
                    axios.post(this.baseURl + 'unsubscribe', _data)
                .then((response) => {
                if (response.data.status == 200) {
                    this.send_data.email = '';
                    toastr.success(response.data.message);
                    close_loader('#page');
                }
                }).catch((error) =>{
                    toastr.error(error.response.data.message);
                    close_loader('#page');
                })
            },

            showSubscribe () {
                this.unsubscribe_form = false;
                this.subscription_form = true;
            },

            showUnsubscribe () {
                this.subscription_form = false;
                this.unsubscribe_form = true;
            },
        },

        computed: {
            baseURl: function () {
                return window.baseURL + "/api/";
            },
        },
    }
</script>
