    
    <div id="file-list-settings">
        
        <ul>
        <li id="file-browse-button-container">
            <a href="#" class="button" id="file-browse-button">Upload files</a>
        </li>
        <li>
            <div id="progress-box"></div>
        </li>
        </ul>

        <div id="progress-template">
            <div class="uploading-wrapper">
                <div class="info"></div>
                <div class="progress"><div class="bar"></div></div>
            </div>
        </div>



    </div>

    <div id="file-list" class="preview-view">

        <div class="file-item-container title">

            <div class="file-item preview"></div>
            <div class="file-item filename">Filename</div>
            <div class="file-item filesize">Info</div>
            <div class="file-item created">Created</div>
            <div class="file-item actions"></div>

        </div>

    </div>

    <script type="text/template" id="file-item-template">

        <div class="file-item-container [[type]] item">

            <div class="file-item preview">
                <img src="/static/admin/default/img/x.gif" data-file="[[uri]]" />
                <span class="fa fa-[[icon]] icon"></span>
            </div>

            <div class="file-item filename">
                [[filename]]
                <div class="uri alt">
                    <a href="[[uri]]" target="_blank"><span class="fa fa-download icon"></span>[[uri]]</a>
                </div>
            </div>

            <div class="file-item filesize">
                [[human_size]]
                <div class="alt">[[type]]</div>
                <div class="img_size alt">[[width]]x[[height]]</div>
            </div>

            <div class="file-item created">
                [[date]]
                <div class="alt">[[time]]</div>
            </div>

            <div class="file-item actions">
                
                {% if status == "select" %}
                    <a href="#" class="select-button" data-uri="[[uri]]" data-id="{{ id }}">Select</a>
                {% else %}
                    <a href="#" class="delete-button" data-file="[[filename]]">
                        <span class="fa fa-trash icon"></span>
                    </a>
                {% endif %}

            </div>

        </div>

    </script>

    <script>

        envvars.delete_csrf_token = '{{ robin.csrf_token('delete-file') }}';

        $(function(){
            FileBrowser.loadFiles();
        });


    </script>
