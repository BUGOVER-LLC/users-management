<template>
    <div ref="footer" style="border-radius: 12px">
        <v-divider class="ma-0" />
        <v-row v-if="paginated" no-gutters>
            <!--     SELECTED EVENTS      -->
            <v-col cols="12" md="4" lg="4" class="d-flex justify-start align-center">
                <div v-if="paginated.selected && paginated.selected.length" class="ml-3">
                    <v-tooltip right>
                        <template #activator="{ on, attrs }">
                            <v-btn
                                fab
                                x-small
                                color="error"
                                outlined
                                v-bind="attrs"
                                v-on="on"
                                @click="deletesSelected()"
                            >
                                <v-icon v-text="'mdi-delete-outline'" />
                            </v-btn>
                        </template>
                        <span>remove marked ones</span>
                    </v-tooltip>
                    <v-tooltip v-if="firstEvent" right>
                        <template #activator="{ on, attrs }">
                            <v-btn
                                fab
                                x-small
                                class="ml-2"
                                outlined
                                color="primary"
                                v-bind="attrs"
                                v-on="on"
                                @click="$emit('firstEvent')"
                            >
                                <v-icon v-text="firstEventIcon" />
                            </v-btn>
                        </template>
                        <span v-text="firstEventText" />
                    </v-tooltip>
                </div>
            </v-col>

            <!--     PAGINATION       -->
            <v-col cols="12" md="4" lg="4" class="d-flex justify-center align-center">
                <v-pagination
                    v-model="paginated.current_page"
                    :disabled="'loading' in paginated && paginated.loading"
                    :length="paginated.last_page"
                    :total-visible="4"
                    color="tertiary"
                />
            </v-col>

            <!--     PER PAGE       -->
            <v-col cols="12" md="4" lg="4" style="margin-left: -30px" class="d-flex justify-end align-center">
                <v-menu class="rounded-b-sm rounded-t-lg" top offset-y>
                    <template #activator="{ on: menu, attrs }">
                        <v-tooltip left>
                            <template #activator="{ on: tooltip }">
                                <v-btn
                                    small
                                    outlined
                                    depressed
                                    dark
                                    color="tertiary"
                                    v-bind="attrs"
                                    v-on="{ ...tooltip, ...menu }"
                                >
                                    {{ paginated.per_page }}
                                </v-btn>
                            </template>
                            <span>lines on the page</span>
                        </v-tooltip>
                    </template>
                    <v-list dense>
                        <v-list-item
                            v-for="(item, index) in [10, 25, 50, 100]"
                            :key="index"
                            dense
                            :disabled="paginated.per_page === item"
                            color="primary"
                            @click="paginated.per_page = item"
                        >
                            <v-list-item-title v-text="item" />
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-col>
        </v-row>
    </div>
</template>

<script lang="ts">
import { Component, Emit, Prop, Vue, Watch } from 'vue-property-decorator';

import { PaginateModel } from '@/producer/store/models/PaginateModel';

@Component
export default class TableFooter extends Vue {
    @Prop({ required: true }) public paginated: PaginateModel;
    @Prop({ required: false }) public firstEvent: boolean;
    @Prop({ required: false }) public firstEventIcon: string;
    @Prop({ required: false }) public firstEventText: string;

    @Watch('paginated.current_page')
    watchCurrentPage(val: number, oldVal: number | null) {
        if (oldVal && val === oldVal) {
            return;
        }

        this.emitPayload({ page: val, per_page: this.paginated.per_page, search: null });
    }

    @Watch('paginated.per_page')
    watchPerPage(val: number, oldVal: number | null) {
        if (oldVal && val === oldVal) {
            return;
        }

        this.emitPayload({ page: this.paginated.current_page, per_page: val, search: null });
    }

    @Emit('changeData')
    emitPayload<T>(result: object): object {
        return result;
    }

    @Emit('deletesSelected')
    deletesSelected() {
        return this.paginated.selected;
    }
}
</script>

<style lang="scss">
.v-menu__content {
    border-radius: 10px !important;
}
</style>
