import { __ } from '@wordpress/i18n';
import CollectionItem from './CollectionItem.jsx';

export const CollectionsList = ({ collections }) => {
  if (!collections || collections.length === 0) {
    return (
      <div className="bg-zinc-900 rounded-lg p-6 text-center border border-zinc-800">
        <p className="text-gray-500">{__('No collections registered.', 'arc-gateway')}</p>
      </div>
    );
  }

  return (
    <div className="bg-zinc-900 rounded-lg overflow-hidden border border-zinc-800">
      <ul className="divide-y divide-zinc-800">
        {collections.map((collection, index) => (
          <CollectionItem key={index} collection={collection} />
        ))}
      </ul>
    </div>
  );
};

export default CollectionsList;
