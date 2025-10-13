import { __ } from '@wordpress/i18n';
import CollectionsHeading from '../components/collections-heading.js';
import CollectionsList from '../components/collections-list.jsx';

const Collections = ({ data }) => {
    return (
        <div className="max-w-4xl">
            <CollectionsHeading />
            <CollectionsList collections={data?.collections} />
        </div>
    );
};

export default Collections;
