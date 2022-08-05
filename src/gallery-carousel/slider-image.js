/**
 * External Dependencies
 */
import classnames from 'classnames';

/**
 * WordPress Dependencies
 */
const { Component, Fragment } = wp.element;
const { Button, Spinner } = wp.components;
const { __, setLocaleData } = wp.i18n;
const { BACKSPACE, DELETE } = wp.keycodes;
const { withSelect } = wp.data;
const { RichText } = wp.blockEditor;
const { isBlobURL } = wp.blob;
const { compose } = wp.compose;

export default function SliderImage( 
{ 
    url, 
    alt, 
    id, 
    linkTo, 
    link, 
    isSelected, 
    onSelect, 
    caption, 
    onRemove, 
    setAttributes, 
} ) {

    // let href;
    // switch ( linkTo ) {
    //     case 'media':
    //         href = url;
    //         break;
    //     case 'attachment':
    //         href = link;
    //         break;
    //     case 'url':
    //         href = link;
    //         break;
    // }

    // console.log(linkTo);

    let captionSelected = false;
    let linkSelected = false;

    const onImageClick = () => {
        if ( ! isSelected ) {
            onSelect();
        }

        if ( captionSelected ) {
            captionSelected = false
        }
    }

    const onSelectCaption = () => {
        if ( ! captionSelected ) {
           captionSelected = true
        }

        if ( ! isSelected ) {
            onSelect();
        }
    }
    const onSelectLink = () => {
        if ( ! linkSelected ) {
           linkSelected = true
        }

        if ( ! isSelected ) {
            onSelect();
        }
    }

    const img = (
        // Disable reason: Image itself is not meant to be interactive, but should
        // direct image selection and unfocus caption fields.
        /* eslint-disable jsx-a11y/no-noninteractive-element-interactions */
        <Fragment>
            <img
                src={ url }
                alt={ alt }
                data-id={ id }
                onClick={ onImageClick }
                tabIndex="0"
                onKeyDown={ onImageClick }
            />
            { isBlobURL( url ) && <Spinner /> }
        </Fragment>
        /* eslint-enable jsx-a11y/no-noninteractive-element-interactions */
    );

    const className = classnames( {
        'is-selected': isSelected,
        'is-transient': isBlobURL( url ),
    } );


    // Disable reason: Each block can be selected by clicking on it and we should keep the same saved markup
    /* eslint-disable jsx-a11y/no-noninteractive-element-interactions, jsx-a11y/onclick-has-role, jsx-a11y/click-events-have-key-events */
    return (
        <figure className={ className }>
            { isSelected &&
                <div className="block-library-gallery-item__inline-menu">
                    <Button
                        onClick={ onRemove }
                        className="blocks-gallery-item__remove"
                        label={ __( 'Remove Image' ) }
                        isSmall
                        isDestructive
                        icon="no-alt"
                    />
                </div>
            }
            { img }
            { ( ! RichText.isEmpty( caption ) || isSelected ) ? (
                <RichText
                    tagName="figcaption"
                    placeholder={ __( 'Write caption…' ) }
                    value={ caption }
                    isSelected={ captionSelected }
                    onChange={ ( newCaption ) => setAttributes( { caption: newCaption } ) }
                    unstableOnFocus={ onSelectCaption }
                    inlineToolbar
                />
            ) : null }
            { ( linkTo === 'url' ) ? (
                <RichText
                    tagName="div"
                    placeholder={ __( 'Enter URL (starting with http:// or https://)' ) }
                    value={ link }
                    isSelected={ linkSelected }
                    onChange={ ( newLink ) => setAttributes( { link: newLink } ) }
                    unstableOnFocus={ onSelectLink }
                    inlineToolbar
                />
            ) : null }
        </figure>
    );
    /* eslint-enable jsx-a11y/no-noninteractive-element-interactions, jsx-a11y/onclick-has-role, jsx-a11y/click-events-have-key-events */
}