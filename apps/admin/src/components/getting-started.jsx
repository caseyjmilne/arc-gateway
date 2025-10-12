export default function GettingStarted() {
    return(
        <article>
            <h2 className="!text-2xl !text-gray-800">Getting Started</h2>
            <ul className="flex flex-col gap-2">
                <li className="text-gray-800">
                    <h3>1. Create a collection class</h3>
                    <p>A collection is a PHP class that extends \ARC\Gateway\Collection.</p>
                    <p>For a minimal start you can provide an Eloquent model and omit any configuration. Gateway will infer a standardized configuration and build functions API routes.</p>
                </li>
                <li className="text-gray-800">
                    <h3>2. Call the collection register() method</h3>
                    <p>Collections use a registry pattern. To register your collection call the register() method from the parent class. If your collection is TicketCollection, call TicketCollection::register().</p>
                </li>
                <li className="text-gray-800">
                    <h3>3. Test the collection routes</h3>
                    <p>Visit the Gateway admin and check for the collection being listed under the Collections page. Then check under the Routes page for the list of routes. We recommend PostMan for testing the function of the registered routes. Typically you will use Basic Authentication to access routes from PostMan, which is an external request. Internal requests (from WordPress itself) will typically use Cookie Authentication.</p>
                </li>
            </ul>
        </article>
    )
}