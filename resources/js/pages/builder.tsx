import Admin from "../layouts/admin";
import React from "react";

const ElementBuilder = React.lazy(() => import('../components/ElementBuilder'));

export default function Builder({ builder }: any) {
    return (
        <Admin>
            <ElementBuilder builder={builder} />
        </Admin>
    );
}
