<h1 align="center">New uploaded images</h1>

<div class="new-uploaded">

    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>
    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>
    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>

    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>

    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>

    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>

    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>

    </div>
    <div class="grid cs-style-1">
        <li>
            <figure>
                <img src="http://bipbap.ru/wp-content/uploads/2017/04/1160334673_2.jpg" alt="img01">
                <figcaption>
                    <h3>Camera</h3>
                    <span>Jacob Cummings</span>
                    <a href="">Take a look</a>
                </figcaption>
            </figure>
        </li>
    </div>
</div>

<h1 align="center">All uploaded images</h1>

<div class="all-uploads">
    @foreach($images as $image)
        <div class="all-uploads-item shadow-border">
            <img src="{{ $image->miniaturePhoto }}" class="uploaded-image">
        </div>
    @endforeach
</div>