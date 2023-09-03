import Admin from "../../layouts/admin";
import '../../../assets/css/media-ineria.css';
import {__} from "../../helpers/functions";
import {Link} from "@inertiajs/react";
import {MediaFile, MediaFolder} from "../../types/media";

export interface MediaFilesProps {
    data: Array<MediaFile>
}

export default function Index({mediaFolders, mediaFiles}: {mediaFolders: Array<MediaFolder>, mediaFiles: MediaFilesProps}) {

    return (
        <Admin>
            <div className="container-m-nx container-m-ny bg-lightest mb-3">
                <div className="file-manager-actions container-p-x py-2">
                    <div>
                        {/*<button type="button" className="btn btn-secondary icon-btn mr-2" disabled={true}>
                            <i className="ion ion-md-cloud-download"></i>
                        </button>*/}
                        <div className="btn-group mr-2">
                            <button type="button" className="btn btn-default md-btn-flat dropdown-toggle px-2" data-toggle="dropdown">
                                <i className="ion ion-ios-settings"></i>
                            </button>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" href="#">Move</a>
                                <a className="dropdown-item" href="#">Copy</a>
                                <a className="dropdown-item" href="#">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" className="btn btn-primary mr-2">
                            <i className="ion ion-md-cloud-upload"></i>&nbsp; Upload
                        </button>
                    </div>
                    {/*<div>
                        <div className="btn-group btn-group-toggle" data-toggle="buttons">
                            <label className="btn btn-default icon-btn md-btn-flat active">
                                <input type="radio" name="file-manager-view" value="file-manager-col-view" checked={true} /> 
                                <span className="ion ion-md-apps"></span>
                            </label>
                            <label className="btn btn-default icon-btn md-btn-flat"> <input type="radio" name="file-manager-view" value="file-manager-row-view" />
                                <span className="ion ion-md-menu"></span> </label>
                        </div>
                    </div>*/}
                </div>

                <hr className="m-0" />
            </div>

            <div className="file-manager-container file-manager-col-view">
                <div className="file-manager-row-header">
                    <div className="file-item-name pb-2">Filename</div>
                    <div className="file-item-changed pb-2">Changed</div>
                </div>

                <div className="file-item">
                    <div className="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
                    <a href="#" className="file-item-name">
                        ..
                    </a>
                </div>

                {mediaFolders.map((folder) => (
                    <div className="file-item">
                        <div className="file-item-select-bg bg-primary"></div>

                        <label className="file-item-checkbox custom-control custom-checkbox">
                            <input type="checkbox" className="custom-control-input" />
                            <span className="custom-control-label"></span>
                        </label>

                        <div className="file-item-icon fa fa-folder text-secondary"></div>
                        <Link href="#" className="file-item-name">
                            {folder.name}
                        </Link>
                        <div className="file-item-changed">02/13/2018</div>
                        <div className="file-item-actions btn-group">
                            <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                            <div className="dropdown-menu dropdown-menu-right">
                                <a className="dropdown-item" href="#">Rename</a>
                                <a className="dropdown-item" href="#">Move</a>
                                <a className="dropdown-item" href="#">Copy</a>
                                <a className="dropdown-item" href="#">Remove</a>
                            </div>
                        </div>
                    </div>
                ))}

                {mediaFiles.data.map((file) => (
                    <div className="file-item">
                        <div className="file-item-select-bg bg-primary"></div>
                        <label className="file-item-checkbox custom-control custom-checkbox">
                            <input type="checkbox" className="custom-control-input" />
                            <span className="custom-control-label"></span>
                        </label>
                        <div className="file-item-icon fa fa-file-archive text-secondary"></div>
                        <a href="#" className="file-item-name">
                            {file.name}
                        </a>
                        <div className="file-item-changed">02/16/2018</div>
                        <div className="file-item-actions btn-group">
                            <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                            <div className="dropdown-menu dropdown-menu-right">
                                <a className="dropdown-item" href="#">Rename</a>
                                <a className="dropdown-item" href="#">Move</a>
                                <a className="dropdown-item" href="#">Copy</a>
                                <a className="dropdown-item" href="#">Remove</a>
                            </div>
                        </div>
                    </div>
                ))}


                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-js text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Build.js
                    </a>
                    <div className="file-item-changed">02/17/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file-word text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Checklist.doc
                    </a>
                    <div className="file-item-changed">02/18/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-html5 text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Index.html
                    </a>
                    <div className="file-item-changed">02/19/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-img"
                         style={{backgroundImage: 'url(https://bootdey.com/img/Content/avatar/avatar1.png)'}}></div>
                    <a href="#" className="file-item-name">
                        Image-1.jpg
                    </a>
                    <div className="file-item-changed">02/20/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-img" style={{backgroundImage: 'url(https://bootdey.com/img/Content/avatar/avatar6.png)'}}></div>
                    <a href="#" className="file-item-name">
                        Image-2.png
                    </a>
                    <div className="file-item-changed">02/21/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-img" style={{backgroundImage: 'url(https://bootdey.com/img/Content/avatar/avatar4.png)'}}></div>
                    <a href="#" className="file-item-name">
                        Image-3.gif
                    </a>
                    <div className="file-item-changed">02/22/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-js text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Main.js
                    </a>
                    <div className="file-item-changed">02/23/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file text-secondary"></div>
                    <a href="#" className="file-item-name">
                        MAKEFILE
                    </a>
                    <div className="file-item-changed">02/24/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file-pdf text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Presentation.pdf
                    </a>
                    <div className="file-item-changed">02/25/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file-alt text-secondary"></div>
                    <a href="#" className="file-item-name">
                        README.txt
                    </a>
                    <div className="file-item-changed">02/26/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-css3 text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Style.css
                    </a>
                    <div className="file-item-changed">02/27/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file-audio text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Test.mp3
                    </a>
                    <div className="file-item-changed">02/28/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>

                <div className="file-item">
                    <div className="file-item-select-bg bg-primary"></div>
                    <label className="file-item-checkbox custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" />
                        <span className="custom-control-label"></span>
                    </label>
                    <div className="file-item-icon fa fa-file-video text-secondary"></div>
                    <a href="#" className="file-item-name">
                        Tutorial.avi
                    </a>
                    <div className="file-item-changed">03/01/2018</div>
                    <div className="file-item-actions btn-group">
                        <button type="button" className="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i className="ion ion-ios-more"></i></button>
                        <div className="dropdown-menu dropdown-menu-right">
                            <a className="dropdown-item" href="#">Rename</a>
                            <a className="dropdown-item" href="#">Move</a>
                            <a className="dropdown-item" href="#">Copy</a>
                            <a className="dropdown-item" href="#">Remove</a>
                        </div>
                    </div>
                </div>
            </div>

            <div className="modal fade" id="upload-modal" tabIndex={-1} role="dialog" aria-labelledby="upload-modal-label" aria-hidden="true">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="upload-modal-label">{__('cms::app.upload')}</h5>
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div className="modal-body">
                            ...
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" className="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </Admin>
    );
}