import { capitalizeFirstLetter } from '@/producer/utils/StringUtils';

export const transformErrors = (rawErrors: null | object): string[] => {
    const errors = Object.entries(rawErrors || { unknown: ['Unknown error occurred'] });
    const res: string[] = [];
    errors.forEach(entry => {
        const key = entry[0];
        const value = entry[1];
        ([value] as string[]).forEach(error => {
            res.push(`${capitalizeFirstLetter(key)} ${error}`);
        });
    });
    return res;
};
