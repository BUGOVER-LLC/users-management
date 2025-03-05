import i18 from '@/producer/plugins/i18n';

export const capitalizeFirstLetter = (str: string): string => str.charAt(0).toUpperCase() + str.slice(1);

export const trans = (value: string, count: number = 1) => i18.tc(value, count);
