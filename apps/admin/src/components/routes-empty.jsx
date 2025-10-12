import { __ } from '@wordpress/i18n';

const RoutesEmpty = () => {
    return (
        <div className="bg-zinc-900 rounded-lg p-6 text-center border border-zinc-800">
            <p className="text-gray-500">{__('No routes registered.', 'arc-gateway')}</p>
        </div>
    );
};

export default RoutesEmpty;
