export interface MediaFile {
    id: number;
    name: string;
    path: string;
    extension: string;
    mime_type: string;
    user_id: number;
    folder_id: number;
    type: string;
    size: number;
    disk: string;
    metadata: any;
}

export interface MediaFolder {
    id: number;
    name: string;
    path: string;
    extension: string;
    mime_type: string;
    user_id: number;
    folder_id: number;
    type: string;
    size: number;
    disk: string;
    metadata: any;
}