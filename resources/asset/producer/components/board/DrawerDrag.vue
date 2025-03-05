<template>
    <v-navigation-drawer
        ref="drawers"
        v-model="navigation.shown"
        :absolute="absolute"
        :mini-variant="mini"
        :right="right"
        :width="navigation.width"
        app
        height="100%"
        dark
        hide-overlay
        permanent
        class="navigation-background"
    >
        <slot v-if="$slots['toolbar']" name="toolbar" />
        <slot v-if="$slots['content']" name="content" />
        <slot v-if="$slots['footer']" name="footer" />
    </v-navigation-drawer>
</template>

<script lang="ts">
import { Component, Prop, Ref, Vue } from 'vue-property-decorator';

import HandlerModule from '@/producer/store/modules/HandlerModule';

@Component
export default class DrawerDrag extends Vue {
    @Prop({ required: false, default: true })
    protected readonly right: boolean;

    @Prop({ default: true, required: false })
    protected readonly absolute: boolean;

    @Ref('drawers') public readonly drawer: any;

    private navigation: any = {
        shown: true,
        width: 320,
        borderSize: 2,
    };

    public get mini(): boolean {
        return HandlerModule.menuMiniVariant;
    }

    get direction(): string {
        return !this.navigation.shown ? 'Open' : 'Closed';
    }

    set direction(val) {
        this.navigation.shown = val;
    }

    mounted() {
        this.setBorderWidth();
        this.setEvents();
    }

    setBorderWidth(): void {
        const drawer = this.drawer.$el.querySelector('.v-navigation-drawer__border');
        drawer.style.width = this.navigation.borderSize + 'px';
        drawer.style.marginTop = 70 + 'px';
        drawer.style.cursor = 'ew-resize';
        drawer.style.backgroundColor = 'darkblue';
    }

    setEvents(): void {
        const minSize = this.navigation.borderSize;
        // @ts-ignore
        const el = this.drawer.$el;
        const drawerBorder = el.querySelector('.v-navigation-drawer__border');
        const direction = el.classList.contains('v-navigation-drawer--right') ? 'right' : 'left';

        /**
         *
         * @param e
         */
        function resize(e: MouseEvent | Touch) {
            document.body.style.cursor = 'ew-resize';
            const f = 'right' === direction ? document.body.scrollWidth - e.clientX : e.clientX;
            el.style.width = `${f}px`;
        }

        drawerBorder.addEventListener(
            'mousedown',
            event => {
                if (event.offsetX < minSize) {
                    el.style.transition = 'initial';
                    document.addEventListener('mousemove', resize, false);
                }
            },
            false,
        );

        document.addEventListener(
            'mouseup',
            () => {
                el.style.transition = '';
                this.navigation.width = el.style.width;
                document.body.style.cursor = '';
                document.removeEventListener('mousemove', resize, false);
            },
            false,
        );
    }
}
</script>

<style scoped lang="scss">
.panel-color {
    background-color: rgb(53, 92, 140);
}
.navigation-background {
    background-image: linear-gradient(
        to right top,
        #d2dbe0,
        #d1dbe1,
        #d0dbe1,
        #cfdbe2,
        #cedbe2,
        #cddbe2,
        #cbdbe3,
        #cadbe3,
        #c8dbe3,
        #c7dae4,
        #c5dae4,
        #c4d9e5
    );
}
</style>
