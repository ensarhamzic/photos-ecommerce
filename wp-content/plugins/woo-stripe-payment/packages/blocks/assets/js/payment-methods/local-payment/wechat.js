import {useEffect, useRef, useState, useCallback} from '@wordpress/element';
import {registerPaymentMethod} from '@woocommerce/blocks-registry';
import {
    getSettings,
    initStripe as loadStripe,
    getDefaultSourceArgs,
    isAddressValid,
    StripeError,
    isTestMode,
    ensureSuccessResponse,
    getErrorMessage,
    storeInCache,
    getFromCache,
    deleteFromCache
} from "../util";
import {PaymentMethodLabel, PaymentMethod} from "../../components/checkout";
import {canMakePayment, LocalPaymentIntentContent} from "./local-payment-method";
import {Elements} from "@stripe/react-stripe-js";
import {useValidateCheckout} from "./hooks";
import {__} from '@wordpress/i18n';
//import QRCode from 'QRCode';
import {useStripe} from "@stripe/react-stripe-js";
import {useStripeError} from "../hooks";

const getData = getSettings('stripe_wechat_data');

const WeChatComponent = (props) => {
    return (
        <Elements stripe={loadStripe}>
            <WeChatPaymentMethod {...props}/>
        </Elements>
    )
}

const WeChatPaymentMethod = (props) => {

    return (
        <>
            <LocalPaymentIntentContent{...props}/>
            <WechatPayInstructions instructions={props.getData('instructions')}/>
        </>
    )
}

const WechatPayInstructions = ({instructions}) => {
    return (
        <ol className={'wc-stripe-wechat-instructions'}>
            {instructions.map(text => (
                <li key={text}>{text}</li>
            ))}
        </ol>
    )
}

if (getData()) {
    registerPaymentMethod({
        name: getData('name'),
        label: <PaymentMethodLabel
            title={getData('title')}
            paymentMethod={getData('name')}
            icons={getData('icon')}/>,
        ariaLabel: 'WeChat',
        canMakePayment: canMakePayment(getData),
        content: <PaymentMethod content={WeChatComponent} getData={getData} confirmationMethod={'confirmWechatPayPayment'}/>,
        edit: <PaymentMethod content={WeChatComponent} getData={getData}/>,
        placeOrderButtonLabel: getData('placeOrderButtonLabel'),
        supports: {
            showSavedCards: false,
            showSaveOption: false,
            features: getData('features')
        }
    })
}
