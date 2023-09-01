import Admin from "../layouts/admin";
import ElementBuilder from "../components/ElementBuilder";

export default function Dashboard({ builder }) {
    return (
        <Admin>
            <ElementBuilder builder={builder} />
        </Admin>
    );
}
