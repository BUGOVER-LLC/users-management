<template lang="html">
    <div class="auth-wrapper d-flex align-center justify-center pa-4">
        <VCard class="auth-card pa-4 pt-7 rounded-lg" max-width="500" elevation="6">
            <v-card-title class="justify-center">
                <VCardTitle class="font-weight-semibold text-2xl text-uppercase">
                    {{ trans('auth.login_description') }}
                </VCardTitle>
            </v-card-title>

            <VCardText>
                <VForm autocomplete="off" @submit.prevent="() => {}">
                    <VRow>
                        <!-- email -->
                        <VCol class="mb-3" cols="12">
                            <v-window v-model="currentStep">
                                <v-window-item eager :value="1">
                                    <EmailSender :email-value="email" @validEmail="emailValidation = $event" />
                                </v-window-item>
                                <v-window-item :value="2">
                                    <ConfirmCode
                                        :disabled-sync="disabledOtp"
                                        @codeValidation="codeValidation = $event"
                                    />
                                </v-window-item>
                            </v-window>
                        </VCol>

                        <v-col cols="12">
                            <div v-if="2 === currentStep" @click="prevStep">
                                <a
                                    class="text-decoration-underline"
                                    @click="currentStep = 1"
                                    v-text="trans('auth.resend_code')"
                                />
                            </div>
                        </v-col>

                        <!-- password -->
                        <VCol cols="12">
                            <!-- login button -->
                            <VBtn
                                :disabled="!emailValidation.valid"
                                :loading="loader"
                                block
                                color="primary"
                                depressed
                                text
                                type="submit"
                                @click="checkSend"
                                v-text="textBtn()"
                            />
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>
    </div>
</template>

<script lang="ts">
import { ValidationProvider } from 'vee-validate';
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import ConfirmCode from '@/producer/components/started/ConfirmCodeComponent.vue';
import EmailSender from '@/producer/components/started/EmailSenderComponent.vue';
import routesNames from '@/producer/router/routesNames';
import AuthModule from '@/producer/store/modules/AuthModule';
import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { ConfirmCode, EmailSender, ValidationProvider },
})
export default class AuthStarted extends Vue implements OnCreated {
    @Prop({ required: true }) public readonly code: boolean;
    @Prop({ required: true }) public readonly email: string;

    public currentStep: number | string = 1;
    public loader: boolean = false;
    public disabledOtp: boolean = false;
    public emailValidation = { valid: false, email: '', sent: false };
    public codeValidation = { valid: false, code: '', sent: false };

    created() {
        this.reDirector();

        if (this.email) {
            this.emailValidation = { valid: true, email: this.email, sent: false };
        }
    }

    @Watch('codeValidation')
    async observeCode(val: string | number) {
        if (val && this.emailValidation.valid && this.codeValidation) {
            this.disabledOtp = true;
            try {
                const result = await AuthModule.addAcceptCode({
                    acceptCode: this.codeValidation.code,
                    email: this.emailValidation.email,
                });
                await this.$router.push({
                    name: routesNames.authPassword,
                    // @ts-ignore
                    params: { passwordAccept: result.passwordConfirm, email: result.email },
                });
            } finally {
                this.disabledOtp = false;
            }
        }
    }

    public prevStep() {
        this.currentStep = 1;
    }

    public nextStep() {
        this.currentStep = 2;
    }

    public textBtn() {
        if (1 === this.currentStep) {
            return this.$tc('words.next', 2);
        }

        return this.$tc('auth.send_code', 1);
    }

    private reDirector() {
        if (this.email) {
            this.nextStep();
        } else {
            this.prevStep();
        }
    }

    public async checkSend() {
        if (this.emailValidation.valid) {
            try {
                await AuthModule.addEmail(this.emailValidation.email);
                this.emailValidation.sent = true;
            } catch (error) {
                console.log(error);
                return;
            } finally {
                this.loader = false;
            }

            this.nextStep();
        }
    }
}
</script>

<style lang="scss" scoped>
.auth-wrapper {
    min-block-size: calc(var(--vh, 1vh) * 100);
}

.auth-footer-mask {
    position: absolute;
    inset-block-end: 0;
    min-inline-size: 100%;
}

.auth-card {
    z-index: 1 !important;
}

.auth-footer-start-tree,
.auth-footer-end-tree {
    position: absolute;
    z-index: 1;
}

.auth-footer-start-tree {
    inset-block-end: 0;
    inset-inline-start: 0;
}

.auth-footer-end-tree {
    inset-block-end: 0;
    inset-inline-end: 0;
}

.auth-illustration {
    z-index: 1;
}

.auth-logo {
    position: absolute;
    z-index: 1;
    inset-block-start: 2rem;
    inset-inline-start: 2.3rem;
}

.auth-bg {
    background-color: rgb(var(--v-theme-surface));
}
</style>
