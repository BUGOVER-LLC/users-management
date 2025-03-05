<template>
    <div>
        <v-divider class="tertiary" />
        <v-list nav rounded>
            <div v-for="(menu, index) in menuItems" :key="index">
                <v-list-item-group v-model="activeMenuId" active-class="deep-purple--text text--accent-2">
                    <router-link :to="{ name: menu.route.name }" class="text-decoration-none">
                        <v-list-item
                            light
                            selectable
                            active-class="accent"
                            link
                            :value="menu.route.name"
                            :input-value="active(menu.route.name)"
                        >
                            <v-tooltip right>
                                <template #activator="{ on }">
                                    <v-list-item-icon v-on="on">
                                        <v-icon v-text="menu.icon" />
                                    </v-list-item-icon>
                                    <v-list-item-content v-on="on">
                                        <span>{{ trans(menu.title[0], menu.title[1]).toUpperCase() }}</span>
                                    </v-list-item-content>
                                </template>
                                <span v-text="menu.description" />
                            </v-tooltip>
                        </v-list-item>
                    </router-link>
                </v-list-item-group>
            </div>
        </v-list>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import menuItems from '@/producer/router/menu-items';

import { trans } from '../../utils/StringUtils';

@Component({
    methods: { trans },
})
export default class MenuList extends Vue {
    public activeMenuId: string | null = null;
    public menuItems = menuItems;

    public active(routeName: string): void {
        if (this.$route.name === routeName) {
            this.activeMenuId = this.$route.name;
        }
    }
}
</script>
