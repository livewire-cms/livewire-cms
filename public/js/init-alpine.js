function data(){return{dark:window.localStorage.getItem("dark")?JSON.parse(window.localStorage.getItem("dark")):!!window.matchMedia&&window.matchMedia("(prefers-color-scheme: dark)").matches,toggleTheme(){var e;this.dark=!this.dark,e=this.dark,window.localStorage.setItem("dark",e)},isSideMenuOpen:!1,toggleSideMenu(){this.isSideMenuOpen=!this.isSideMenuOpen},closeSideMenu(){this.isSideMenuOpen=!1},isNotificationsMenuOpen:!1,toggleNotificationsMenu(){this.isNotificationsMenuOpen=!this.isNotificationsMenuOpen},closeNotificationsMenu(){this.isNotificationsMenuOpen=!1},isProfileMenuOpen:!1,toggleProfileMenu(){this.isProfileMenuOpen=!this.isProfileMenuOpen},closeProfileMenu(){this.isProfileMenuOpen=!1},isPagesMenuOpen:!1,togglePagesMenu(){this.isPagesMenuOpen=!this.isPagesMenuOpen},isModalOpen:!1,trapCleanup:null,openModal(){this.isModalOpen=!0,this.trapCleanup=focusTrap(document.querySelector("#modal"))},closeModal(){this.isModalOpen=!1,this.trapCleanup()}}}
