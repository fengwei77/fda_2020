import { Locator } from '../types';
export declare function isNaN(value: unknown): boolean;
export declare function isNullOrUndefined(value: unknown): value is undefined | null;
export declare function isEmptyArray(arr: any[]): boolean;
export declare const isObject: (obj: unknown) => obj is {
    [x: string]: any;
};
/**
 * A reference comparison function with NaN support
 */
export declare function isRefEqual(lhs: any, rhs: any): boolean;
/**
 * Shallow object comparison.
 */
export declare function isEqual(lhs: any, rhs: any): boolean;
export declare function isSpecified(val: string | null | undefined): boolean;
export declare function isCallable(fn: unknown): fn is Function;
export declare function isLocator(value: unknown): value is Locator;
