<template>
    <div>
        <div id="preloader">
            <div id="status">
                <div class="spinner-chase">
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                </div>
            </div>
        </div>
        <div id="layout-wrapper">
            <wew />
            <HorizontalNavigation v-if="$page.props.auth.data.role == 'Regional Director'"/>
            <div class="main-content" style="margin-top: -40px;" v-if="$page.props.auth.data.role != 'Regional Director'">
                <div class="page-content" style="margin-bottom: -35px;">
                    <div class="account-pages px-4">
                        <Profile />
                        <div class="row g-3">
                            <Navigation />
                            <div class="col-md-9">
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-content" v-else>
                <div class="page-content">
                    <div class="container-fluid">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HorizontalNavigation from "@/Shared/Layout/Horizontal/HorizontalNavigation";
    import Director from "@/Shared/Layout/Horizontal/Director.vue";
    import Navigation from "@/Shared/Layout/Horizontal/Navigation.vue";
    import Profile from "@/Shared/Layout/Horizontal/Profile.vue";
    import wew from "@/Shared/Layout/Horizontal/NavBar.vue";
    export default {
        props: ['auth'],
        components: {
            wew,
            Profile,
            Navigation,
            Director,
            HorizontalNavigation
        },
        data() {
            return {
                isMenuCondensed: false,
            };
        },
        created: () => {
            document.body.setAttribute("data-layout", "horizontal");
            document.body.setAttribute("data-topbar", "dark");
            document.body.removeAttribute("data-sidebar", "dark");
            document.body.removeAttribute("data-layout-size", "boxed");
        },
        mounted() {
            const layout = JSON.parse(localStorage.getItem("layout")) || {};
            if (layout.loader == true) {
                document.getElementById("preloader").style.display = "block";
                document.getElementById("status").style.display = "block";

                setTimeout(function () {
                    document.getElementById("preloader").style.display = "none";
                    document.getElementById("status").style.display = "none";
                }, 2500);
            } else {
                document.getElementById("preloader").style.display = "none";
                document.getElementById("status").style.display = "none";
            }
        },
        methods: {
            toggleMenu() {
                document.body.classList.toggle("sidebar-enable");

                if (window.screen.width >= 992) {
                    document.body.classList.toggle("vertical-collpsed");
                } else {
                    document.body.classList.remove("vertical-collpsed");
                }
                this.isMenuCondensed = !this.isMenuCondensed;
            },
            toggleRightSidebar() {
                document.body.classList.toggle("right-bar-enabled");
            },
            hideRightSidebar() {
                document.body.classList.remove("right-bar-enabled");
            }
        }
    };

</script>
