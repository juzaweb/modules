import Button, {ButtonProps} from "@/components/elements/buttons/button";

export default function SuccessButton(props: ButtonProps) {
    return (
        <Button {...props} color={'success'} />
    );
}
