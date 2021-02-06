<?php

namespace Repository;

use Entity\Semester;
use Entity\Club;
use Entity\Course;
use Doctrine\ORM\EntityRepository;
use Entity\Child;
use Entity\Participant;
use Entity\User;

class ParticipantRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Participant[]
     */
    public function findByUserThisAndLaterSemesters(User $user)
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
            ->andWhere('participant.user = :user')
            ->setParameter('user', $user)
            ->andWhere('semester.year >= :year')
            ->setParameter('year', $year);
        if (!$isSpring) {
            $query->andWhere('semester.isSpring = false');
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return Participant[]
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->where('participant.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User   $user
     * @param Course $course
     *
     * @return Participant|null
     */
    public function findOneByUserAndCourse(User $user, Course $course)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->where('participant.user = :user')
            ->andWhere('participant.course = :course')
            ->setParameter('user', $user)
            ->setParameter('course', $course)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User     $user
     * @param Semester $semester
     *
     * @return \Entity\Participant[]
     */
    public function findByUserAndSemester(User $user, Semester $semester)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('participant.user = :user')
            ->andWhere('course.semester = :semester')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('user', $user)
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Course $course
     * @param Child  $child
     *
     * @return Participant | null
     */
    public function findByCourseAndChild(Course $course, Child $child)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->where('participant.course = :course')
            ->andWhere('participant.child = :child')
            ->setParameter('course', $course)
            ->setParameter('child', $child)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Semester $semester
     * @param Club     $club
     *
     * @return \Entity\Participant[]
     */
    public function findBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('course.semester = :semester')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->andWhere('courseType.club = :club')
            ->setParameter('semester', $semester)
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Club $club
     *
     * @return Participant[]
     */
    public function findByClub(Club $club)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('courseType.club = :club')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }
}
