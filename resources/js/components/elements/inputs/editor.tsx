import React, {useState} from "react";

const CKEditor = React.lazy(() => import('@ckeditor/ckeditor5-react'));
const ClassicEditor = React.lazy(() => import('@ckeditor/ckeditor5-build-classic'));

export interface EditorProps {
    name?: string;
    id?: string;
    label?: string;
    type?: string;
    value?: string;
    class?: string;
}

export default function Editor(props: EditorProps) {
    const [data, setData] = useState(props.value);

    const handleChange = (event: any, editor: any) => {
        const data = editor.getData();

        console.log( { event, editor, data } );

        setData(data);
    }

    return <div className={'form-group'}>
        <label className="col-form-label" htmlFor={props.id}>{props.label}</label>
        <textarea className={'box-hidden'+ (props.class ? ' ' + props.class : '')} name={props.name}>{data}</textarea>

        <CKEditor
            editor={ ClassicEditor }
            id={props.id}
            data={data}
            onReady={ editor => {
                console.log( 'Editor is ready to use!', editor );
            } }
            onChange={ handleChange }
            onBlur={ ( event, editor ) => {
                console.log( 'Blur.', editor );
            } }
            onFocus={ ( event, editor ) => {
                console.log( 'Focus.', editor );
            } }
        />
    </div>
}