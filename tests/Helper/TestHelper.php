<?php

namespace CodePrimer\Tests\Helper;

use CodePrimer\Helper\BusinessBundleHelper;
use CodePrimer\Helper\FieldType;
use CodePrimer\Model\BusinessBundle;
use CodePrimer\Model\BusinessModel;
use CodePrimer\Model\Constraint;
use CodePrimer\Model\Field;

class TestHelper
{
    /**
     * @param bool $withBusinessModels
     */
    public static function getSamplePackage($withBusinessModels = true, $withRelationships = true): BusinessBundle
    {
        $package = new BusinessBundle('CodePrimer Tests', 'FunctionalTest');

        if ($withBusinessModels) {
            self::addSampleBusinessModels($package);
        }

        if ($withRelationships) {
            // Build the relationships between the entities
            $packageHelper = new BusinessBundleHelper();
            $packageHelper->buildRelationships($package);
        }

        return $package;
    }

    public static function addSampleBusinessModels(BusinessBundle $package)
    {
        $businessModel = new  BusinessModel('User', 'This entity represents a user');
        $businessModel
            ->setAudited(true)
            ->addField(
                (new Field('id', FieldType::UUID, "The user's unique ID in our system"))
                    ->setMandatory(true)
                    ->setManaged(true)
                    ->setExample('b34d38eb-1164-4289-98b4-65706837c4d7')
            )
            ->addField(
                (new Field('firstName', FieldType::STRING, 'User first name'))
                    ->setExample('John')
                    ->setSearchable(true)
            )
            ->addField(
                (new Field('lastName', FieldType::STRING, 'User last name'))
                    ->setExample('Doe')
                    ->setSearchable(true)
            )
            ->addField(
                (new Field('nickname', FieldType::STRING, 'The name used to identify this user publicly on the site'))
                    ->setExample('JohnDoe')
                    ->setSearchable(true)
            )
            ->addField(
                (new Field('email', FieldType::EMAIL, 'User email address'))
                    ->setMandatory(true)
                    ->setSearchable(true)
            )
            ->addField(
                (new Field('password', FieldType::PASSWORD, 'User password'))
                    ->setMandatory(true)
            )
            ->addField(
                (new Field('created', FieldType::DATETIME, 'The date and time at which this user was created'))
                    ->setManaged(true)
            )
            ->addField(
                (new Field('updated', FieldType::DATETIME, 'The date and time at which this user was updated'))
                    ->setManaged(true)
            )
            ->addField(
                (new Field('crmId', FieldType::STRING, 'The ID of this user in our external CRM'))
                    ->setExample('2c3b1c3e-b29c-4564-80c4-e4b95cfbfc81')
            )
            ->addField(
                (new Field('activationCode', FieldType::RANDOM_STRING, 'The code required to validate the user\'s account'))
                    ->setManaged(true)
                    ->setExample('qlcS7L')
            )
            ->addField(new Field('stats', 'UserStats', 'User login statistics'))
            ->addField(new Field('subscription', 'Subscription', 'The plan to which the user is subscribed'))
            ->addField(
                (new Field('metadata', 'Metadata', 'Extra information about the user'))
                    ->setList(true)
            )
            ->addField(
                (new Field('posts', 'Post', 'Blog posts created by this user'))
                    ->setList(true)
            )
            ->addField(
                (new Field('topics', 'Topic', 'List of topics this user to allowed to create posts for'))
                    ->setList(true)
            );

        $businessModel
            ->addUniqueConstraint(
                (new Constraint('uniqueEmail'))
                    ->addField($businessModel->getField('email'))
                    ->setDescription('The email address must uniquely identify the user for login in')
                    ->setErrorMessage('This email address is already in use. Please select another one or recover your password if you forgot it.')
            )
            ->addUniqueConstraint(
                (new Constraint('uniqueNickname'))
                    ->addField($businessModel->getField('nickname'))
                    ->setDescription('The nickname uniquely identifies the user in the site\'s public spaces')
                    ->setErrorMessage('This nickname name is already in use. Please select another one.')
        );

        $package->addBusinessModel($businessModel);

        $businessModel = new BusinessModel('UserStats', 'Simple statistics about the user');
        $businessModel
            ->addField(new Field('firstLogin', FieldType::DATETIME, 'First time the user logged in the system'))
            ->addField(new Field('lastLogin', FieldType::DATETIME, 'Last time the user logged in the system'))
            ->addField(new Field('loginCount', FieldType::LONG, 'Number of time the user logged in the system'));

        $package->addBusinessModel($businessModel);

        $businessModel = new BusinessModel('Metadata', 'Variable set of extra information');
        $businessModel
            ->addField(
                (new Field('name', FieldType::STRING, 'The name to uniquely identify this metadata'))
                    ->setMandatory(true)
            )
            ->addField(
                (new Field('value', FieldType::TEXT, 'The value associated with this metadata'))
                    ->setMandatory(true)
            );

        $package->addBusinessModel($businessModel);

        $businessModel = new BusinessModel('Post', 'Post created by the user');
        $businessModel
            ->addField(new Field('title', FieldType::STRING, 'The post title', true))
            ->addField(new Field('body', FieldType::TEXT, 'The post body', true))
            ->addField(new Field('author', 'User', 'The user who created this post', true))
            ->addField(new Field('topic', 'Topic', 'The topic to which this post belongs', true))
            ->addField(
                (new Field('created', FieldType::DATETIME, 'Time at which the post was created'))
                    ->setManaged(true)
            )
            ->addField(
                (new Field('updated', FieldType::DATETIME, 'Last time at which the post was updated'))
                    ->setManaged(true)
            );

        $package->addBusinessModel($businessModel);

        $businessModel = new  BusinessModel('Topic', 'A topic regroups a set of posts made by various authors');
        $businessModel
            ->addField(new Field('title', FieldType::STRING, 'The topic title', true))
            ->addField(new Field('description', FieldType::TEXT, 'The topic description'))
            ->addField(
                (new Field('authors', 'User', 'List of authors who are allowed to post on this topic'))
                    ->setList(true)
            )
            ->addField(
                (new Field('posts', 'Post', 'List of posts published on this topic'))
                    ->setList(true)
            )
            ->addField(
                (new Field('created', FieldType::DATETIME, 'Time at which the post was created'))
                    ->setManaged(true)
            )
            ->addField(
                (new Field('updated', FieldType::DATETIME, 'Last time at which the post was updated'))
                    ->setManaged(true)
            );

        $package->addBusinessModel($businessModel);

        $businessModel = new BusinessModel('Subscription', 'The subscription bought by a user to user our services');
        $businessModel
            ->addField(new Field('user', 'User', 'The user to which this subscription belongs', true))
            ->addField(new Field('plan', FieldType::STRING, 'The plan subscribed by this user in our billing system', true))
            ->addField(new Field('renewal', FieldType::DATE, 'The date at which the subscription must be renewed', true))
            ->addField(
                (new Field('created', FieldType::DATETIME, 'Time at which the post was created'))
                    ->setManaged(true)
            )
            ->addField(
                (new Field('updated', FieldType::DATETIME, 'Last time at which the post was updated'))
                    ->setManaged(true)
            );

        $package->addBusinessModel($businessModel);
    }
}
