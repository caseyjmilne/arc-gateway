export const CollectionItem = ({ collection }) => {
  return (
    <li className="px-6 py-4">
      <div className="flex items-center justify-between">
        <div>
          <span className="font-medium text-gray-300">
            {collection.alias}
          </span>
          <span className="ml-3 text-sm text-gray-500">
            ({collection.class})
          </span>
        </div>
      </div>
    </li>
  );
};

export default CollectionItem;
