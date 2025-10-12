import { __ } from '@wordpress/i18n';

export default function RoutesHeading() {
    return(
        <h2 className="!text-lg !font-semibold !text-gray-800 mb-4">
            {__('Registered Routes', 'arc-gateway')}
        </h2>
    )
}