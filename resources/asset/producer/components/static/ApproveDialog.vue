<template>
    <v-card prepend-icon="mdi-map-marker">
        <v-card-title>{{ title }}</v-card-title>

        <v-card-text>{{ content }}</v-card-text>

        <v-card-actions>
            <v-spacer />

            <v-btn text @click="dialog = false"> {{ trans('words.disagree') }} </v-btn>
            <v-btn depressed @click="dialog = true">
                <span class="text--secondary">{{ trans('auth.send_code') }}</span>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script lang="ts">
import { Component, Prop, PropSync, Vue } from 'vue-property-decorator';

import { trans } from '../../utils/StringUtils';

@Component({
    methods: { trans },
})
export default class ApproveDialog extends Vue {
    @PropSync('value', { required: true }) public dialog: boolean;
    @Prop({ required: false }) public readonly title: string;
    @Prop({ required: false }) public readonly content: string;
}
</script>
