<script setup>
import { ref } from 'vue';
import LinkItem from '@/Components/LinkItem.vue';
import { usePage } from '@inertiajs/vue3';
const menuItems = ref([
      { name: 'Dashboard', route: route('admin.index'), icon: 'mdi-view-dashboard'},
      { name: 'Categories', route: route('admin.categories.index'), icon: 'mdi-filter-variant' },
      { name: 'Menu', route: route('admin.menus.index'), icon: 'mdi-food' },
      { name: 'Tables', route: route('admin.tables.index'), icon: 'mdi-table-furniture' },
      { name: 'Reservations', route: route('admin.reservations.index'), icon: 'mdi-silverware' },

      // Add more menu items here
    ]);
const page = usePage();
const drawer = ref(true);
const rail = ref(false);
</script>

<template>
 <v-app id="inspire">
    <v-navigation-drawer
    :rail="rail"
    v-model="drawer"
    width="280"
    permanent
    @click="rail = false"
            >
            <v-img
                src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                height="200px"
                cover
                ></v-img>
                <v-divider></v-divider>
                <v-list density="compact" class="px-2">
                    <LinkItem v-for="item in menuItems" :item="item" :rail="rail" :key="item.name" ></LinkItem>
                </v-list>
                <template v-slot:append>
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-list>
                                <v-list-item
                                    prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
                                    :title="$page.props.auth.user.name"
                                    :subtitle="$page.props.auth.user.email"
                                    v-bind="props"
                                ></v-list-item>
                            </v-list>
                        </template>
                        <v-list> 
                            <v-list-item  prepend-icon="mdi-cog">
                                <Link :href="route('profile.edit')"> Profile </Link>
                            </v-list-item>
                            <v-list-item prepend-icon="mdi-logout"> 
                                <Link :href="route('logout')" method="post" as="button">Logout</Link>
                            </v-list-item>
                            
                        </v-list>
                    </v-menu>
                </template>
            </v-navigation-drawer>



    <v-app-bar>
        <v-btn size="large" :icon="!rail ? 'mdi-close' :'mdi-menu'" color="warning" @click="rail = !rail"></v-btn>
      <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>

    <v-main>
                <slot />
            </v-main>
  </v-app>
</template>
  


<style>
.v-list-item{
    margin-top: 10px;
}
.v-list-item--active {
    background-color: rgb(var(--v-theme-primary));
    color: rgb(var(--v-theme-light));
    box-shadow: 5px 5px 0px 0px rgba(65,117,5,0.4),
                10px 10px 0px 0px rgba(65,117,5,0.2);
}
.v-navigation-drawer--rail  .v-list-item--active{
    box-shadow: 3px 3px 0px 0px rgba(65,117,5,0.4) !important;
}
.v-navigation-drawer {
    box-shadow: 5px 5px 0px 0px rgba(65,117,5,0.4),
10px 10px 0px 0px rgba(65,117,5,0.2),
15px 15px 0px 0px rgba(65,117,5,0.1);
}
a{
    color: #000000DE;
}
</style>