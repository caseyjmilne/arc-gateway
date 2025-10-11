import { __ } from '@wordpress/i18n';

export default function CollectionsHeading() {
    return(
        <h2 className="!text-lg !font-semibold !text-gray-300 mb-4">
            {__('Registered Collections', 'arc-gateway')}
        </h2>
    )
}